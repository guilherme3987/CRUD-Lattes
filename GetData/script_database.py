from urllib.parse import urlparse  # <-- Certifique-se de que este import está aqui

import os
import xml.etree.ElementTree as ET
import mysql.connector
import hashlib
import unicodedata
from datetime import datetime
from dotenv import load_dotenv

load_dotenv()

def conectar_mysql():
    """Configura e retorna a conexão interpretando a Service URI do Aiven."""
    try:
        # Pega a Service URI do arquivo .env
        database_url = os.getenv("DATABASE_URL")
        
        if not database_url:
            print("Erro: A variável DATABASE_URL não foi encontrada no arquivo .env.")
            return None
            
        # Faz o parse da URL automaticamente
        url = urlparse(database_url)
        
        # Limpa a barra inicial do path para obter o nome exato do banco
        nome_banco = url.path.lstrip('/')
        
        # Conecta separando os argumentos extraídos da URI
        conn = mysql.connector.connect(
            host=url.hostname,
            user=url.username,
            password=url.password,
            port=url.port ,
            database=nome_banco,
            charset="utf8mb4",
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Erro ao conectar ao MySQL no Aiven: {err}")
        return None
# --- Funções de Tratamento e Regras de Negócio ---
def tratar_texto(valor, padrao="Não informado"):
    return valor if valor and str(valor).strip() else padrao

def tratar_ano(valor, padrao_vazio):
    if valor and str(valor).isdigit():
        ano = int(valor)
        if 1901 <= ano <= 2155:
            return str(ano)
    return str(padrao_vazio)

def gerar_email(nome_completo):
    """
    Remove acentos, pega o primeiro e último nome e cria o email: nome_sobrenome@gmail.com
    """
    if not nome_completo or nome_completo == "Não informado":
        return f"usuario_{datetime.now().strftime('%Y%m%d%H%M%S')}@gmail.com"

    # Remove acentos e caracteres especiais (ex: João -> Joao)
    nfkd_form = unicodedata.normalize('NFKD', nome_completo)
    nome_limpo = u"".join([c for c in nfkd_form if not unicodedata.combining(c)])
    
    # Divide os nomes e pega o primeiro e o último
    partes = nome_limpo.lower().split()
    if len(partes) > 1:
        email = f"{partes[0]}_{partes[-1]}@gmail.com"
    else:
        email = f"{partes[0]}@gmail.com"
        
    return email

# -----------------------------

def extrair_e_inserir_xml(caminho_xml, conn):
    cursor = conn.cursor()
    ano_atual = datetime.now().year

    try:
        parser = ET.XMLParser(encoding="ISO-8859-1")
        tree = ET.parse(caminho_xml, parser=parser)
        root = tree.getroot()

        id_lattes = root.attrib.get("NUMERO-IDENTIFICADOR")
        if not id_lattes:
            return

        dados_gerais = root.find("DADOS-GERAIS")
        if dados_gerais is None:
            return

        # --- DADOS DO PESQUISADOR E GERAÇÃO DE LOGIN ---
        nome_completo = tratar_texto(dados_gerais.attrib.get("NOME-COMPLETO"))
        
        # Gera email dinamicamente
        email = gerar_email(nome_completo)
        
        # Gera a senha "123456" em hash MD5
        senha_md5 = hashlib.md5("123456".encode('utf-8')).hexdigest()

        pais_nascimento = tratar_texto(dados_gerais.attrib.get("PAIS-DE-NASCIMENTO"))
        cidade_nascimento = tratar_texto(dados_gerais.attrib.get("CIDADE-NASCIMENTO"))
        orcid_id = tratar_texto(dados_gerais.attrib.get("ORCID-ID"))

        resumo_node = dados_gerais.find("RESUMO-CV")
        resumo_cv = tratar_texto(resumo_node.attrib.get("TEXTO-RESUMO-CV-RH")) if resumo_node is not None else "Não informado"

        # Inserção do Pesquisador (Agora com email e senha_md5)
        sql_pesquisador = """
            INSERT IGNORE INTO pesquisador (id_lattes, email, senha, nome_completo, pais_nascimento, cidade_nascimento, orcid_id, resumo_cv)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        cursor.execute(
            sql_pesquisador,
            (
                id_lattes,
                email,
                senha_md5,
                nome_completo,
                pais_nascimento,
                cidade_nascimento,
                orcid_id,
                resumo_cv,
            ),
        )

        # --- PROCESSAMENTO DE FORMAÇÃO ACADÊMICA ---
        formacao = dados_gerais.find("FORMACAO-ACADEMICA-TITULACAO")
        if formacao is not None:
            tags_formacao = ["GRADUACAO", "ESPECIALIZACAO", "MESTRADO", "DOUTORADO", "POS-DOUTORADO"]
            for tag in tags_formacao:
                for item in formacao.findall(tag):
                    ano_inicio = tratar_ano(item.attrib.get("ANO-DE-INICIO"), 1901)
                    ano_conclusao = tratar_ano(item.attrib.get("ANO-DE-CONCLUSAO"), ano_atual)
                    
                    sql_formacao = """
                        INSERT INTO formacao_academica (id_lattes, nivel, instituicao, curso, status, ano_inicio, ano_conclusao)
                        VALUES (%s, %s, %s, %s, %s, %s, %s)
                    """
                    cursor.execute(
                        sql_formacao,
                        (
                            id_lattes,
                            tag,
                            tratar_texto(item.attrib.get("NOME-INSTITUICAO")),
                            tratar_texto(item.attrib.get("NOME-CURSO")),
                            tratar_texto(item.attrib.get("STATUS-DO-CURSO"), "CONCLUIDO"),
                            ano_inicio,
                            ano_conclusao,
                        ),
                    )

        # --- PROCESSAMENTO DE ATUAÇÃO PROFISSIONAL ---
        atuacoes = dados_gerais.find("ATUACOES-PROFISSIONAIS")
        if atuacoes is not None:
            for item in atuacoes.findall("ATUACAO-PROFISSIONAL"):
                inst = tratar_texto(item.attrib.get("NOME-INSTITUICAO"))
                vinculos = item.findall("VINCULOS")
                for v in vinculos:
                    ano_inicio = tratar_ano(v.attrib.get("ANO-INICIO"), 1901)
                    ano_fim = tratar_ano(v.attrib.get("ANO-FIM"), ano_atual)

                    sql_atuacao = """
                        INSERT INTO atuacao_profissional (id_lattes, instituicao, ano_inicio, ano_fim, tipo_vinculo, enquadramento_funcional)
                        VALUES (%s, %s, %s, %s, %s, %s)
                    """
                    cursor.execute(
                        sql_atuacao,
                        (
                            id_lattes,
                            inst,
                            ano_inicio,
                            ano_fim,
                            tratar_texto(v.attrib.get("TIPO-DE-VINCULO")),
                            tratar_texto(v.attrib.get("ENQUADRAMENTO-FUNCIONAL")),
                        ),
                    )

        conn.commit()
        print(f"Sucesso: {nome_completo} (Login: {email})")

    except Exception as e:
        conn.rollback()
        print(f"Erro ao processar o arquivo {caminho_xml}: {e}")
    finally:
        cursor.close()

def processar_pasta_lattes_mysql(pasta_origem):
    conn = conectar_mysql()
    if not conn:
        print("Cancelando operação por falta de conexão com o Banco.")
        return

    print("Conectado ao MySQL com sucesso. Iniciando importação com geração de logins...")
    arquivos = [f for f in os.listdir(pasta_origem) if f.lower().endswith(".xml")]

    if not arquivos:
        print(f"Nenhum arquivo .xml encontrado.")
        conn.close()
        return

    for arquivo in arquivos:
        caminho_completo = os.path.join(pasta_origem, arquivo)
        extrair_e_inserir_xml(caminho_completo, conn)

    conn.close()
    print("\nTodos os dados foram processados e salvos!")

if __name__ == "__main__":
    PASTA_FONTES = "./lattes_data_xml_formatados"
    if not os.path.exists(PASTA_FONTES):
        print(f"Erro: A pasta '{PASTA_FONTES}' não foi encontrada.")
    else:
        processar_pasta_lattes_mysql(PASTA_FONTES)