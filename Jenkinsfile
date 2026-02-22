pipeline {
  agent any
  stages {
    stage('install') {
      steps {
        sh 'npm ci'
      }
    }
    stage('build') {
      steps {
        sh 'npm run build'
      }
    }
    stage('test') {
      steps {
        sh 'npm test'
      }
    }
    stage('extract data') {
      steps {
        sh 'npm run extract-data'
      }
    }
    stage('deploy to platform') {
      when {
        anyOf {
          branch 'master'
        }
      }
      steps {
        sh '''
          rm -rf /rgaa-tanaguru/*
          cp -r ./* /rgaa-tanaguru/
        '''
      }
    }
  }
}
