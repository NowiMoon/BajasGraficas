from flask import Flask, request, jsonify
from fuzzywuzzy import process

app = Flask(__name__)

# Lista de nombres de materias normalizados (ampliada y con variantes)
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

def manejar_casos_especificos(entrada):
    entrada = entrada.lower().strip()

    # Casos para "edo"
    if entrada.startswith("edo"):
        if entrada == "edo a":
            return "Estructuras de Datos I", ["Estructuras de Datos I"]
        elif entrada == "edo b":
            return "Estructuras de Datos II", ["Estructuras de Datos II"]
        elif entrada == "edo c" or entrada == "edo avanzadas":
            return "Estructuras de Datos Avanzadas", ["Estructuras de Datos Avanzadas"]
        else:
            opciones_edo = [
                "Estructuras de Datos I",
                "Estructuras de Datos II",
                "Estructuras de Datos Avanzadas"
            ]
            return None, opciones_edo

    # Casos para "proyectos"
    elif entrada.startswith("proyectos") or entrada.startswith("pro")or entrada.startswith("programacion")or entrada.startswith("pc"):
        if entrada == "proyectos 2" or entrada == "proyectos ii" or entrada == "pc ii"or entrada == "pc 2"or entrada == "pc2":
            return "Proyectos Computacionales II", ["Proyectos Computacionales II"]
        elif entrada == "proyectos 3" or entrada == "proyectos iii"or entrada == "pc iii"or entrada == "pc 3"or entrada == "pc3":
            return "Proyectos Computacionales III", ["Proyectos Computacionales III"]
        elif entrada == "proyectos" or entrada == "pc":
            return "Proyectos Computacionales", ["Proyectos Computacionales"]
        elif entrada == "programacion" or entrada == "prom objetos":
            return "Programación Orientada a Objetos", ["Programación Orientada a Objetos"]
        else:
            opciones_proyectos = [
                "Proyectos Computacionales I",
                "Proyectos Computacionales II",
                "Proyectos Computacionales III",
                "Programación Orientada a Objetos"
            ]
            return None, opciones_proyectos

    # Si no coincide con ningún caso específico
    return None, []


# Función para normalizar una entrada
def normalizar_materia(entrada, opciones, umbral=60):
    mejor_coincidencia_especifica, opciones_especificas = manejar_casos_especificos(entrada)
    if mejor_coincidencia_especifica:
        return mejor_coincidencia_especifica, opciones_especificas
    elif opciones_especificas:
        return None, opciones_especificas
    
    posibles_coincidencias = process.extract(entrada, opciones, limit=5)
    coincidencias_filtradas = [opcion for opcion, puntaje in posibles_coincidencias if puntaje >= umbral]
    
    if coincidencias_filtradas:
        mejor_coincidencia = coincidencias_filtradas[0]
        return mejor_coincidencia, coincidencias_filtradas
    else:
        return None, [opcion for opcion, _ in posibles_coincidencias]

# Ruta de la API
@app.route('/normalizar', methods=['POST'])
def normalizar():
    data = request.json
    entrada = data.get('entrada', '')
    mejor_coincidencia, opciones = normalizar_materia(entrada, materias_normalizadas)
    return jsonify({
        'mejor_coincidencia': mejor_coincidencia,
        'opciones': opciones
    })

if __name__ == '__main__':
    app.run(debug=True)