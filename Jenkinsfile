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

        
        stages {
            stage('Deploy') {
                steps {
                    sshagent(['ec2-ssh-key']) {
                        sh """
                            rsync -avz ./ \$EC2_HOST:/var/www/laravel-app
                            ssh \$EC2_HOST "cd /var/www/laravel-app && ./vendor/bin/sail up -d"
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
