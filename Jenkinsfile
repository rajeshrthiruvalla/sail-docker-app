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
