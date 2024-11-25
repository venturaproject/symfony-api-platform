# Usa una imagen base de Node.js
FROM node:20

# Define an argument for the version
ARG VERSION

# Label the image with the version
LABEL version=${VERSION}

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia el package.json y el package-lock.json desde el directorio correcto
COPY ./frontend/package.json ./
COPY ./frontend/package-lock.json ./

# Instala las dependencias
RUN npm install

# Copia el resto de la aplicación
COPY ./frontend ./

# Expone el puerto que usará la aplicación
EXPOSE 3000

# Comando para iniciar la aplicación
CMD ["npm", "run", "dev"]

