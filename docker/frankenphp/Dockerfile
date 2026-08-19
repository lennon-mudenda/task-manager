FROM dunglas/frankenphp:php8.5

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        zsh \
        libzip-dev \
        libicu-dev \
        libonig-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        zip \
        bcmath \
        pcntl \
        mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------
# Oh My Zsh
# ---------------------------------------------------------

RUN RUNZSH=no \
    CHSH=no \
    KEEP_ZSHRC=yes \
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

RUN git clone --depth=1 \
        https://github.com/zsh-users/zsh-autosuggestions \
        "${ZSH_CUSTOM:-/root/.oh-my-zsh/custom}/plugins/zsh-autosuggestions" \
    && git clone --depth=1 \
        https://github.com/zsh-users/zsh-syntax-highlighting \
        "${ZSH_CUSTOM:-/root/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting"

RUN sed -i \
    's/plugins=(git)/plugins=(git composer docker zsh-autosuggestions zsh-syntax-highlighting)/' \
    /root/.zshrc

# ---------------------------------------------------------
# Redis
# ---------------------------------------------------------

RUN pecl install redis \
    && docker-php-ext-enable redis

# ---------------------------------------------------------
# Bun
# ---------------------------------------------------------

ENV BUN_INSTALL="/root/.bun"

RUN curl -fsSL https://bun.com/install | bash

ENV PATH="${BUN_INSTALL}/bin:${PATH}"

# ---------------------------------------------------------
# Composer
# ---------------------------------------------------------

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# ---------------------------------------------------------
# Bun dependencies
# ---------------------------------------------------------

COPY package.json bun.lock ./

RUN bun install --frozen-lockfile

# ---------------------------------------------------------
# Application
# ---------------------------------------------------------

COPY . .

RUN cp .env.example .env || true

COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80 443 5173

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
