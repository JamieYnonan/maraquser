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

        stage('Unit Test') {
            sh 'make test'
        }
    } catch (exception) {
        echo '(TODO) send email fail!'
    }
}