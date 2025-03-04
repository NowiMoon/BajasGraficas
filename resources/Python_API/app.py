from flask import Flask, request, jsonify
from fuzzywuzzy import process

app = Flask(__name__)

# Lista de materias y escuelas
materias_normalizadas = [
    "Estructuras de Datos I",
    "Estructuras de Datos II",
    "Estructuras de Datos avanzadas",
    "Programación Orientada a Objetos",
    "Bases de Datos avanzadas",
    "Administración de Bases de Datos",
    "Redes de Computadoras",
    "Sistemas Operativos",
    "Inteligencia Artificial",
    "Machine Learning",
    "Ingeniería de Software",
    "Desarrollo de Aplicaciones Web",
    "Desarrollo de Aplicaciones Móviles",
    "Arquitectura de Computadoras",
    "Lenguajes de Programación",
    "Compiladores",
    "Cloud Computing",
    "Matemáticas Discretas",
    "Cálculo A",
    "Cálculo B",
    "Probabilidad y Estadística",
    "Sistemas Embebidos",
    "Robótica",
    "Bases de Datos",
    "Física",
    "Proyectos computacionales I",
    "Proyectos computacionales II",
    "Proyectos computacionales III",
    "Redes de Computadoras"
]
trabajos_normalizados = [
    "BOCH",
    "GOOGLE",
    "HONEYHELL",
    "DAIKIN",
    "ABB"
]
escuelas_normalizadas = [
    "CBTIS 50", "CBTIS 119", "CBTIS 121", "CBTIS 123", "CBTIS 124", "CBTIS 125",
    "CBTIS 126", "CBTIS 168", "CBTIS 185", "CBTIS 194", "CBTIS 195", "CBTIS 213", "CBTIS 214",
    "CBTIS 215", "CBTIS 216", "CBTIS 217", "COBACH 01", "COBACH 02", "COBACH 03",
    "COBACH 04", "COBACH 05", "COBACH 06", "COBACH 07", "COBACH 08", "COBACH 09",
    "COBACH 10", "COBACH 11", "COBACH 12", "COBACH 13", "COBACH 14", "COBACH 15",
    "Preparatoria Central", "Preparatoria Ponciano Arriaga",
    "Preparatoria Enrique Rébsamen", "Preparatoria Marista",
    "Preparatoria del Instituto Potosino", "PrepaTec San Luis Potosí",
    "Preparatoria del Instituto Tecnológico de San Luis Potosí",
    "Preparatoria del Instituto Cultural Tampico",
    "Preparatoria del Colegio Simón Bolívar",
    "Preparatoria del Colegio Juana de Asbaje", "ENP (Escuela Nacional Preparatoria)",
    "CCH (Colegio de Ciencias y Humanidades)",
    "CECyT (Centro de Estudios Científicos y Tecnológicos)",
    "Preparatoria 1 - UANL", "Preparatoria 2 - UANL", "Preparatoria 3 - UANL",
    "Bachillerato de la UAQ", "Preparatoria 1 - UADY", "Preparatoria 2 - UADY",
    "Bachillerato de la UAA", "Bachillerato de la UAZ", "Bachillerato de la UG",
    "Bachillerato de la BUAP"
]

def manejar_casos_especificos(entrada):
    entrada = entrada.lower().strip()
    if entrada.startswith("edo"):
        if entrada == "edo a":
            return "Estructuras de Datos I", ["Estructuras de Datos I"]
        elif entrada == "edo b":
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

def normalizar_materia(entrada, opciones, umbral=60):
    mejor_coincidencia_especifica, opciones_especificas = manejar_casos_especificos(entrada)
    if mejor_coincidencia_especifica:
        return mejor_coincidencia_especifica, opciones_especificas
    elif opciones_especificas:
        return None, opciones_especificas
    posibles_coincidencias = process.extract(entrada, opciones, limit=5)
    coincidencias_filtradas = [opcion for opcion, puntaje in posibles_coincidencias if puntaje >= umbral]
    if coincidencias_filtradas:
        return coincidencias_filtradas[0], coincidencias_filtradas
    else:
        return None, [opcion for opcion, _ in posibles_coincidencias]




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



@app.route('/normalizar/materia', methods=['POST'])
def endpoint_normalizar_materia():
    data = request.json
    entradas = data.get('entradas', [])
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400
    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_materia(entrada, materias_normalizadas)
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
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400
    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_escuela(entrada, escuelas_normalizadas)
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
    
    if not entradas or not isinstance(entradas, list):
        return jsonify({'error': 'No se recibieron entradas válidas'}), 400

    resultados = []
    for entrada in entradas:
        mejor_coincidencia, opciones = normalizar_trabajo(entrada, trabajos_normalizados)
        resultados.append({
            'entrada': entrada,
            'mejor_coincidencia': mejor_coincidencia,
            'opciones': opciones[:3]
        })

    return jsonify({'resultados': resultados})

if __name__ == '__main__':
    app.run(debug=True)
