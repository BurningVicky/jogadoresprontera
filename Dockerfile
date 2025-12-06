FROM php:8.2-cli
WORKDIR /app

# Copia todos os arquivos para dentro do container
COPY . .

# Porta que a API vai rodar no Render
EXPOSE 10000

# Sobe servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:10000", "api.php"]
