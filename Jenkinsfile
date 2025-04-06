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

        stage('Deploy') {
            steps {
                sshagent(['ec2-ssh-key']) {
                    sh """
                        echo "🔄 Syncing code to EC2..."
                        rsync -avz --exclude='vendor' --exclude='.env' ./ \$EC2_HOST:/var/www/laravel-app

                        echo "🚀 Installing dependencies and bringing up Sail..."
                        ssh \$EC2_HOST << 'EOF'
                            cd /var/www/laravel-app

                            echo "📦 Running Composer install..."
                            docker run --rm -v \$(pwd):/app -w /app composer:2 composer install

                            echo "⬆️ Starting Laravel Sail..."
                            ./vendor/bin/sail up -d
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
