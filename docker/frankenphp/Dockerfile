FROM dunglas/frankenphp:php8.5

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zsh \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        zip \
        bcmath \
        pcntl \
        exif \
        mbstring \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Oh My Zsh without launching Zsh during the image build
RUN RUNZSH=no \
    CHSH=no \
    KEEP_ZSHRC=yes \
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Optional Zsh plugins
RUN git clone --depth=1 \
        https://github.com/zsh-users/zsh-autosuggestions \
        "${ZSH_CUSTOM:-/root/.oh-my-zsh/custom}/plugins/zsh-autosuggestions" \
    && git clone --depth=1 \
        https://github.com/zsh-users/zsh-syntax-highlighting \
        "${ZSH_CUSTOM:-/root/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting"

# Enable plugins
RUN sed -i \
    's/plugins=(git)/plugins=(git composer docker zsh-autosuggestions zsh-syntax-highlighting)/' \
    /root/.zshrc

# Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN cp .env.example .env || true

COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80 443

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
