pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'rajeshthiruvalla/laravel-app'
        DOCKER_TAG = 'latest'
        EC2_HOST = 'ubuntu@13.201.74.47'
        EC2_KEY = credentials('ec2-ssh-key') // Jenkins SSH key credential
    }

    stages {
        stage('Clone Repository') {
            steps {
                checkout scm
            }
        }
        stage('Copy .env  from Jenkins') {
            steps {
                // Replace with actual path where the files are located on Jenkins server
                sh '''
                    cp /var/lib/jenkins/configs/.env "$WORKSPACE/.env"
                '''
            }
        }
        stage('Build Docker Image') {
            steps {
                sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} ."
            }
        }

        stage('Push to Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'docker-hub-creds', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh '''
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push ${DOCKER_IMAGE}:${DOCKER_TAG}
                    '''
                }
            }
        }

        stage('Deploy to EC2') {
            steps {
                sshagent (credentials: ['ec2-ssh-key']) {
                    sh """
                        ssh -o StrictHostKeyChecking=no $EC2_HOST '
                            docker pull ${DOCKER_IMAGE}:${DOCKER_TAG} &&
                            docker stop docker-app || true &&
                            docker rm docker-app || true &&
                            docker run -d --name docker-app -p 80:80 ${DOCKER_IMAGE}:${DOCKER_TAG}
                        '
                    """
                }
            }
        }
    }

    post {
        always {
            cleanWs()
        }
    }
}
