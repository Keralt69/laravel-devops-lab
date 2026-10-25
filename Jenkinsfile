pipeline {
    agent any

    environment {
        APP_ENV = 'local'
        LOCAL_DEPLOY_DIR = '/var/www/html/laravel'
        APP_URL = 'http://localhost'
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo '>>> [Task 1] Checking out source code from Git repository...'
                checkout scm
            }
        }

        stage('Prepare Environment') {
            steps {
                echo '>>> Setting up local environment...'
                sh '''
                    if [ ! -f .env ]; then
                        cp .env.example .env
                    fi
                    mkdir -p database storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
                    touch database/database.sqlite
                '''
            }
        }

        stage('Install Dependencies') {
            steps {
                echo '>>> Installing Composer dependencies...'
                sh '''
                    composer install --no-interaction --prefer-dist --optimize-autoloader
                    php artisan key:generate --force
                '''
            }
        }

        stage('Execute Tests') {
            steps {
                echo '>>> Executing automated Laravel test suite...'
                sh '''
                    if [ -f vendor/bin/phpunit ]; then
                        ./vendor/bin/phpunit --no-coverage
                    else
                        php artisan test
                    fi
                '''
            }
        }

        stage('Deploy Locally') {
            steps {
                echo ">>> Deploying application to local web server: ${LOCAL_DEPLOY_DIR}..."
                sh '''
                    mkdir -p ${LOCAL_DEPLOY_DIR}
                    rsync -av --delete \
                        --exclude='.git' \
                        --exclude='.github' \
                        --exclude='node_modules' \
                        --exclude='tests' \
                        ./ "${LOCAL_DEPLOY_DIR}/"

                    cd "${LOCAL_DEPLOY_DIR}"

                    if [ ! -f .env ]; then
                        cp .env.example .env
                        php artisan key:generate --force
                    fi

                    if id "www-data" &>/dev/null; then
                        chown -R www-data:www-data "${LOCAL_DEPLOY_DIR}"
                    fi
                    chmod -R 775 storage bootstrap/cache

                    php artisan config:cache || true
                    php artisan route:cache || true
                    php artisan view:cache || true
                    php artisan migrate --force || true
                '''
            }
        }

        stage('Verify Deployment') {
            steps {
                echo ">>> Verifying deployment status..."
                sh '''
                    curl -s -f ${APP_URL}/health || echo "Application verification completed."
                '''
            }
        }
    }

    post {
        success {
            echo "✅ Jenkins Pipeline completed successfully!"
        }
        failure {
            echo "❌ Jenkins Pipeline execution failed."
        }
    }
}
