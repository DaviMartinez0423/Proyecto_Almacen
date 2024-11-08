# Aplicacion Web Airbnb

Este proyecto presenta una aplicación de procesamiento y visualización de datos diseñada para trabajar en un entorno distribuido. Consta de dos partes principales:

1. **Aplicacion Principal**: Ejecuta la aplicacion web
2. **Aplicacion PySpark**: Realiza el tratamiento de datos de un dataset derivado de la aplicacion principal

## Requisitos

1. **Docker**: Asegurarse de tener Docker instalado en tu máquina.
2. **Docker Compose**: Asegurarse de tener Docker Compose instalado.

Asegurarse tambien de tener espacio para ejecutar el proyecto sin problemas

## Estructura del Proyecto

La estructura del proyecto es la siguiente:

## Descripción de los Archivos Principales

- **transferencia_df**: Contiene el ejecutable que inserta la informacion a la base de datos
- **docker-compose.yml**: Define los contenedores para ejecutar la aplicación, incluyendo Apache Spark, Hadoop y la aplicación Python para el análisis.
- **haproxy**: Contiene los archivos de la herramienta haproxy, la cual realizara el balanceo de carga
- **web**: Contiene los archvivos de las paginas web
- **salida**: Contiene la aplicacion PySpark para el procesamiento de datos
- **microservicios**: Los microservicios empleados por la aplicacion
-  **db**: Contiene los archivos necesarios para ejecutar la creacion de las bases de datos

## Instrucciones para Ejecutar la Aplicación

### 1. Clonar el Repositorio

Si no tienes el proyecto en tu máquina, clónalo utilizando Git:

```bash
git https://github.com/DaviMartinez0423/Proyecto_Almacen
cd Proyecto_Infraestructura
```

### 2. Descarcar PySpark

Una vez dentro del repositorio, ejecuta la siguiente linea:

```bash
wget https://dlcdn.apache.org/spark/spark3.5.3/spark-3.5.3-bin-hadoop3.tgz
```
```bash
tar -xvzf spark-3.5.0-bin-hadoop3.tgz
```

### 2. Construir y Levantar los Contenedores

Ejecuta el siguiente comando para construir y levantar los contenedores de Docker:

```bash
docker-compose up -d --build
```

### 3. Ver los Resultados
Los resultados del análisis se almacenan en el directorio output. Los archivos generados son los siguientes:

-  price_stats.csv
-  availability_stats.csv
-  reviews_stats.csv
-  room_type_distribution.csv
-  price_outliers.csv
-  monthly_reviews.csv

### Contacto

Si tiene alguna pregunta, puede comunicarse a nuestros correos electronicos

-  david_fel.martinez@uao.edu.co
-  miguel.ruales@uao.edu.co
-  Nicolas.cuaran@uao.edu.co