pipeline {
    agent any

    environment {
        EC2_HOST = 'ubuntu@52.66.174.38'
        EC2_KEY = credentials('ec2-ssh-key') // Jenkins SSH key credential
    }

    stages {
        stage('Clone Repository') {
            steps {
                checkout scm
            }
        }

        stage('Deploy to EC2 with Sail') {
            steps {
                sshagent([env.EC2_KEY]) {
                    sh """
                        echo "🔄 Syncing code to remote EC2..."
                        rsync -avz --exclude='vendor' --exclude='.env' ./ \$EC2_HOST:/var/www/laravel-app

                        echo "🚀 Running Sail and Laravel setup on EC2..."
                        ssh \$EC2_HOST << 'EOF'
                            cd /var/www/laravel-app

                            echo "📦 Installing dependencies inside Sail..."
                            ./vendor/bin/sail run --rm composer install --no-interaction --prefer-dist --optimize-autoloader

                            echo "⬆️ Starting Sail services..."
                            ./vendor/bin/sail up -d

                            echo "🛠 Running migrations..."
                            ./vendor/bin/sail artisan migrate --force
                        EOF
                    """
                }
            }
        }
    }

    post {
        success {
            echo "✅ Laravel deployed to EC2 successfully!"
        }
        failure {
            echo "❌ Deployment failed. Check logs."
        }
    }
}
