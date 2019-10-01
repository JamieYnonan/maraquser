node {
    checkout scm

    stage('Build') {
        sh "make build_cli_image"
    }

    stage('Unit Test') {
        sh "make test"
    }
}