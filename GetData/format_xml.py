import os
import xml.etree.ElementTree as ET
from xml.dom import minidom


def formatar_e_salvar_xml(caminho_origem, caminho_destino):
    """Lê um arquivo XML, aplica a indentação (pretty print) e o salva de forma organizada."""
    try:
        # 1. Faz o parse do arquivo original respeitando o encoding ISO-8859-1 do Lattes
        parser = ET.XMLParser(encoding="ISO-8859-1")
        tree = ET.parse(caminho_origem, parser=parser)
        root = tree.getroot()

        # 2. Converte o ElementTree de volta para string XML (em bytes)
        xml_string_bruta = ET.tostring(root, encoding="ISO-8859-1")

        # 3. Utiliza o minidom para parsear a string e estruturar a árvore
        dom = minidom.parseString(xml_string_bruta)

        # 4. Remove espaços em branco extras e aplica recuo de 4 espaços
        xml_formatado = dom.toprettyxml(indent="    ", encoding="ISO-8859-1")

        # 5. Salva o arquivo final já formatado na pasta de destino
        with open(caminho_destino, "wb") as f:
            f.write(xml_formatado)

        print(f"Sucesso ao formatar: {os.path.basename(caminho_origem)}")

    except Exception as e:
        print(f"Erro ao formatar o arquivo {caminho_origem}: {e}")


def processar_pasta_lattes(pasta_origem, pasta_destino):
    """Varre a pasta de origem e exporta todos os arquivos formatados na pasta de destino."""
    # Cria a pasta de destino se ela não existir
    if not os.path.exists(pasta_destino):
        os.makedirs(pasta_destino)

    # Lista todos os arquivos .xml da pasta informada
    arquivos = [
        f for f in os.listdir(pasta_origem) if f.lower().endswith(".xml")
    ]

    if not arquivos:
        print(
            f"Nenhum arquivo .xml encontrado na pasta de origem: '{pasta_origem}'"
        )
        return

    print(f"Iniciando a formatação de {len(arquivos)} arquivos XML...\n")

    for arquivo in arquivos:
        caminho_origem = os.path.join(pasta_origem, arquivo)
        caminho_destino = os.path.join(pasta_destino, arquivo)

        formatar_e_salvar_xml(caminho_origem, caminho_destino)

    print(
        f"\nProntinho! Todos os arquivos formatados foram salvos em: '{pasta_destino}'"
    )


# --- Execução do Script ---
if __name__ == "__main__":
    # CONFIGURAÇÃO DOS CAMINHOS (Alterado para sua pasta específica)
    PASTA_ORIGEM = "./lattes_data_xml"
    PASTA_DESTINO = "./lattes_data_xml_formatados"

    # Verifica se a pasta informada realmente existe antes de iniciar
    if not os.path.exists(PASTA_ORIGEM):
        print(
            f"Erro: A pasta de origem '{PASTA_ORIGEM}' não foi encontrada."
        )
        print(
            "Certifique-se de que a pasta está no mesmo diretório deste script ou corrija o caminho."
        )
    else:
        processar_pasta_lattes(PASTA_ORIGEM, PASTA_DESTINO)