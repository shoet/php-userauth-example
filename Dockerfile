# PHPの公式イメージをベースとして使用
# FROM php:8.0-apache
FROM arm64v8/php:8.1-apache

RUN apt-get update
RUN apt-get install -y libicu-dev libzip-dev

# 必要なPHPの拡張機能をインストール
# CodeIgniter 4の要件に応じて拡張機能を選択
RUN docker-php-ext-install pdo pdo_mysql intl zip mysqli

# Apacheのmod_rewriteモジュールを有効にする
RUN a2enmod rewrite

# Apacheのドキュメントルートを設定
# CodeIgniter 4ではpublicディレクトリがドキュメントルートとなる
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Apacheの設定を更新して、新しいドキュメントルートを反映
RUN sed -i -e 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/*.conf
RUN sed -i -e 's|/var/www/|${APACHE_DOCUMENT_ROOT}/|g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Composerをインストール
# CodeIgniter 4の依存関係を管理するために使用
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

# 作業ディレクトリを設定
WORKDIR /var/www/html

# コンテナのポート80を外部に公開
EXPOSE 80

# ソースコードをコンテナにコピー
# .dockerignoreファイルで不要なファイルは除外する
COPY ./php-userauth-example /var/www/html/

# Composerを使用してPHPの依存関係をインストール
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer update
RUN composer install

# 権限を調整
RUN chown -R www-data:www-data /var/www/html
