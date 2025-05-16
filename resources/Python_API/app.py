from flask import Flask, request, jsonify
from fuzzywuzzy import process

app = Flask(__name__)

# Listas de datos normalizados
materias_normalizadas = [
    "Química A","Fundamentos de Compiladores","Matemáticas Discretas I","Pensamiento Algorítmico","Temas Selectos de Matemáticas","Herramientas de Software","Metodología de la Investigación"
    ,"Seminario de Orientación en Computación","Cálculo A","Matemáticas Discretas II","Estucturas de Datos I","Álgebra B","Fundamentos de Circuitos Eléctricos","Inglés 1","Cálculo B"
    ,"Ingeniería de Software","Estucturas de Datos","Estucturas de Datos II","Lenguajes de Programación","Dispositivos Semiconductores","Tendencias Sociales","Cálculo D","Tecnología Orientada a Objetos",
    "Algoritmos y complejidad","Base de Datos","Fundamentos de Diseño Digital","Gestión y Desarrollo Social","Inglés 2","Análisis Numérico","Interfaces gráficas con aplicaciones","Gestión de Servidores y Seguridad",
    "Física A","Diseño Digital","Inglés 3","Probabilidad y Estadística","Estructuras de datos Avanzadas","Diseño e Implementación de Redes","Sistemas Operativos","Diseño de Microcomputadoras","Arte, Cultura y Humanidades I","Inglés 4","Seminario de Medio término","Administración de Proyectos I","Fundamentos de Compiladores","Microcontroladores","Técnicas de Comunicación Oral y Escrita","Inglés 5","Proyectos Computacionales I","Administración de Proyectos II","Procesamiento de Señales","Liderazgo","Proyectos Computacionales II","Fundamentos de Software de sistemas","Emprendimiento","Proyectos Compútacionales III","Seminario de Egreso","Computación y Sociedad","Actividades Artisticas, Deportivas o de Divulgación","Actividades de aprendizaje I","Actividades de aprendizaje II","Actividades de aprendizaje III","Actividades de aprendizaje IV","Actividades de aprendizaje V","Movilidad I","Movilidad III","Movilidad III","Movilidad IV","Movilidad V","Sistemas Operativos Avanzados","Fundamentos de Inteligencia Artificial","Supercómputo","Administración de Base de Datos","Arte, Cultura y Humanidades II *","Robótica","Sistemas Embebidos","Automatización","Control Digital","Interfaces Digitales de comunicaciones","Principios de Cómputo en la Nube","Administración de Redes","Arquitectura de Nube","Interacción de redes","Servicios en la Nube","Modelado y simulación de redes",
    "Fundamentos de Desarrollo web","Fundamentos de Desarrollo móvil","Aplicaciones web interactivas","Diseño de interfaces","Aplicaciones web escalables","Introducción a los sistemas Geoespaciales","Visión Computacional","Base de datos Geoespaciales","Geointeligencia artificial aplicada a la teledetección","Geoaplicaciones web y móviles","Prácticas Profesionales Computación","Programación de Robots","Cómputo Bio-Inspirado","Aprendizaje automático","Robótica inteligente","Ciencia de datos","Representación del conocimiento y ontologías","Programación de videojuegos","Diseño de juegos","Motores gráficos","Arte conceptual para videojuegos","Temas Selectos de videojuegos","Principios de seguridad informática","Criptografía","Anonimato y privacidad","Prácticas Profesionales ISI","Sistemas Interactivos","Arquitectura de Computadoras","Graficación por computadoras","Modelado Matemático"

]

trabajos_normalizados = ["ABB","Daikin","Honeywell","Siemens","Bosch","GE","3M","Rockwell Automation","Schneider Electric","LG","Panasonic","Xerox","Qualcomm","Broadcom","Micron","Texas Instruments","ARM","TSMC","Tesla","Waymo","Ford","GM","Toyota","BMW","Mercedes","Volkswagen","Rivian","Lucid Motors","Canva","Discord","Airbnb","DoorDash","Instacart","Patreon","Reddit","Byju's","ABB","Daikin","Honeywell","Siemens""Google","Microsoft","Apple","Amazon","Meta","IBM","Oracle","Intel","AMD","NVIDIA","Netflix","Uber","Lyft","OpenAI","SpaceX","Palantir","Stripe","Robinhood","Databricks","Salesforce","Spotify","Twitter","LinkedIn","Snapchat","TikTok","Zoom","Cisco","HP","Dell","Samsung","Goldman Sachs","JPMorgan Chase","PayPal","Coinbase","BBVA","Santander","Citibank","Morgan Stanley","American Express","Visa","Mastercard","Revolut","Nubank","Kavak","Rappi","Accenture","Deloitte","PwC","EY","KPMG","McKinsey","BCG","Bain","Capgemini","Infosys","AT&T","Verizon","Ericsson","Huawei","T-Mobile","Telefónica","Claro","Movistar","America Móvil","EA","Ubisoft","Activision Blizzard","Riot Games","Epic Games","Valve","Nintendo","Sony","Bandai Namco","Square Enix","NASA","Lockheed Martin","Boeing","Raytheon","NSA","CIA","FBI","Walmart","Alibaba","MercadoLibre","Shopify","eBay","Target","Best Buy","Allegro","SEARS","Johnson & Johnson","Pfizer","Moderna","Roche","Merck","Siemens Healthineers","Philips"]

escuelas_normalizadas = [
    "COBACH 01","COBACH 02","COBACH 03","COBACH 04","COBACH 05","COBACH 06","COBACH 07","COBACH 08","COBACH 09","COBACH 10","COBACH 11","COBACH 12","COBACH 13","COBACH 14","COBACH 15","COBACH 16","COBACH 17","COBACH 18","COBACH 19","COBACH 20","COBACH 21","COBACH 22","COBACH 23","COBACH 24","COBACH 25","COBACH 26","COBACH 27","COBACH 28","COBACH 29","COBACH 30","COBACH 31","COBACH 32","COBACH 33","COBACH 34","COBACH 35","COBACH 36","COBACH 37","COBACH 38","COBACH 39","COBACH 40",
    "CBTIS 51","CBTIS 121","CBTIS 128","CBTIS 137","CBTIS 150","CBTIS 168","CBTIS 169","CBTIS 170","CBTIS 171","CBTIS 172","CBTIS 173","CBTIS 174","CBTIS 175","CBTIS 176","CBTIS 177","CBTIS 178","CBTIS 179","CBTIS 180","CBTIS 181","CBTIS 182","CBTIS 183","CBTIS 184","CBTIS 185","CBTIS 186","CBTIS 187","CBTIS 188","CBTIS 189","CBTIS 190","CBTIS 191","CBTIS 192","CBTIS 193","CBTIS 194","CBTIS 195","CBTIS 196","CBTIS 197","CBTIS 198","CBTIS 199","CBTIS 200","CBTIS 201","CBTIS 202","CBTIS 203","CBTIS 204","CBTIS 205","CBTIS 206","CBTIS 207","CBTIS 208","CBTIS 209","CBTIS 210","CBTIS 211","CBTIS 212","CBTIS 213","CBTIS 214","CBTIS 215","CBTIS 216","CBTIS 217","CBTIS 218","CBTIS 219","CBTIS 220",
    "CONALEP 1","CONALEP 2","CONALEP 3","CONALEP 4","CONALEP 5"
    "Preparatoria Central", "Preparatoria Ponciano Arriaga",
    "Preparatoria Enrique Rébsamen", "Instituto Potosino Marista", "Preparatoria del Instituto Potosino",
    "PrepaTec San Luis Potosí", "Preparatoria del Instituto Tecnológico de San Luis Potosí",
    "Preparatoria del Instituto Cultural Tampico", "Preparatoria del Colegio Simón Bolívar",
    "Preparatoria del Colegio Juana de Asbaje", "ENP (Escuela Nacional Preparatoria)",
    "CCH (Colegio de Ciencias y Humanidades)", "CECyT (Centro de Estudios Científicos y Tecnológicos)",
    "Preparatoria 1 - UANL", "UANL", "Preparatoria 3 - UANL",
    "Bachillerato de la UAQ","Alfa y omega",
    "Bachillerato de la UAA", "Bachillerato de la UAZ", "Bachillerato de la UG",
    "Bachillerato de la BUAP","Prepa Anáhuac","Prepa Marista","Prepa Motolinía","Instituto Salesiano",
    "Prepa Cuauhtémoc","Colegio Terranova","Instituto Hispano Inglés","Instituto Potosino","Francisco Martínez",
    "Lic.Antonio Rocha cordero","Rafael Nieto","San Luis Rey","Instituto San Rafael","José Juárez","Celia Fernández","Juana de Asbaje","El Saucito",
    "José Natividad","Luis Medellín","6 Junio","Solidaridad","Juan Salinas","Justo Sierra","Municipal 4","EMSAD 01",
    "Prepa en Línea SEP","BETIS 1","BETIS 2","BETIS 3","BETIS 4","BETIS 5","BETIS 6","BETIS 7","BETIS 8","BETIS 9","BETIS 10"
]

# Función para manejo de casos específicos en materias
def manejar_casos_especificos(entrada):
    entrada = entrada.lower().strip()
    if entrada.startswith("edo")or entrada.startswith("estru"):
        if entrada == "edo a":
            return "Estructuras de Datos I", ["Estructuras de Datos I"]
        elif entrada in ["edo 2", "estructuras ii", "estructuras 2","Estructuras de Datos 2","Estructuras de datos 2"]:
            return "Estructuras de Datos II", ["Estructuras de Datos II"]
        elif entrada in ["edo c", "edo avanzadas"]:
            return "Estructuras de Datos Avanzadas", ["Estructuras de Datos Avanzadas"]
        else:
            opciones_edo = [
                "Estructuras de Datos I",
                "Estructuras de Datos II",
                "Estructuras de Datos Avanzadas"
            ]
            return None, opciones_edo
    elif (entrada.startswith("proyectos") or entrada.startswith("pro") or 
        entrada.startswith("programacion") or entrada.startswith("pc")):
        if entrada in ["proyectos 2", "proyectos ii", "pc ii", "pc 2", "pc2"]:
            return "Proyectos Computacionales II", ["Proyectos Computacionales II"]
        elif entrada in ["proyectos 3", "proyectos iii", "pc iii", "pc 3", "pc3"]:
            return "Proyectos Computacionales III", ["Proyectos Computacionales III"]
        elif entrada in ["proyectos", "pc"]:
            return "Proyectos Computacionales", ["Proyectos Computacionales"]
        elif entrada in ["programacion", "prom objetos"]:
            return "Programación Orientada a Objetos", ["Programación Orientada a Objetos"]
        else:
            opciones_proyectos = [
                "Proyectos Computacionales I",
                "Proyectos Computacionales II",
                "Proyectos Computacionales III",
                "Programación Orientada a Objetos"
            ]
            return None, opciones_proyectos
    return None, []

# Función de normalización genérica para materias
def normalizar_materia(entrada, opciones, umbral=60):
    mejor_coincidencia_especifica, opciones_especificas = manejar_casos_especificos(entrada)
    if mejor_coincidencia_especifica:
        return mejor_coincidencia_especifica, opciones_especificas
    
    posibles_coincidencias = process.extract(entrada, opciones, limit=5)
    coincidencias_filtradas = [opcion for opcion, puntaje in posibles_coincidencias if puntaje >= umbral]
    if coincidencias_filtradas:
        return coincidencias_filtradas[0], coincidencias_filtradas
    else:
        return None, [opcion for opcion, _ in posibles_coincidencias]

# Funciones de normalización para escuelas y trabajos
def normalizar_escuela(entrada, opciones, umbral=60):
    posibles_coincidencias = process.extract(entrada, opciones, limit=5)
    coincidencias_filtradas = [opcion for opcion, puntaje in posibles_coincidencias if puntaje >= umbral]
    if coincidencias_filtradas:
        return coincidencias_filtradas[0], coincidencias_filtradas
    else:
        return None, [opcion for opcion, _ in posibles_coincidencias]

def normalizar_trabajo(entrada, opciones, umbral=60):
    posibles_coincidencias = process.extract(entrada, opciones, limit=5)
    coincidencias_filtradas = [opcion for opcion, puntaje in posibles_coincidencias if puntaje >= umbral]
    if coincidencias_filtradas:
        return coincidencias_filtradas[0], coincidencias_filtradas
    else:
        return None, [opcion for opcion, _ in posibles_coincidencias]

# Endpoints con soporte al parámetro 'umbral'
@app.route('/normalizar/materia', methods=['POST'])
def endpoint_normalizar_materia():
    data = request.json
    entradas = data.get('entradas', [])
    umbral = data.get('umbral', 60)
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400
    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_materia(entrada, materias_normalizadas, umbral)
        resultados.append({
            'entrada': entrada,
            'mejor_coincidencia': mejor_coincidencia,
            'opciones': opciones[:3]
        })
    return jsonify({'resultados': resultados})

@app.route('/normalizar/escuela', methods=['POST'])
def endpoint_normalizar_escuela():
    data = request.json
    entradas = data.get('entradas', [])
    umbral = data.get('umbral', 60)
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400
    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_escuela(entrada, escuelas_normalizadas, umbral)
        resultados.append({
            'entrada': entrada,
            'mejor_coincidencia': mejor_coincidencia,
            'opciones': opciones[:3]
        })
    return jsonify({'resultados': resultados})

@app.route('/normalizar/trabajos', methods=['POST'])
def endpoint_normalizar_trabajos():
    data = request.json
    entradas = data.get('entradas', [])
    umbral = data.get('umbral', 60)
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400
    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_trabajo(entrada, trabajos_normalizados, umbral)
        resultados.append({
            'entrada': entrada,
            'mejor_coincidencia': mejor_coincidencia,
            'opciones': opciones[:3]
        })
    return jsonify({'resultados': resultados})

if __name__ == '__main__':
    app.run(debug=True)