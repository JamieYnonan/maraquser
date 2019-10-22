node {
    try {
        stage ('SCM') {
            checkout scm
        }

        stage('Build') {
            sh 'make build_cli_image'
            sh 'make composer COMMAND="install --no-ansi --no-cache --no-interaction"'
        }

        stage('Check Style') {
            sh 'make composer COMMAND="check-style"'
        }

        stage('Unit Testing') {
            sh 'make test'
        }

        stage('Mutation Testing') {
            sh 'make infection'
        }
    } catch (exception) {
        echo '(TODO) send email fail!'
        throw exception
    }
}