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
                sshagent([env.EC2_KEY]) {
                    sh """
                        echo "🔄 Syncing code to EC2..."
                        rsync -avz --exclude='vendor' --exclude='.env' ./ \$EC2_HOST:/var/www/laravel-app

                        echo "🚀 Bringing up Laravel app using Sail on EC2..."
                        ssh \$EC2_HOST << 'EOF'
                            cd /var/www/laravel-app
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
