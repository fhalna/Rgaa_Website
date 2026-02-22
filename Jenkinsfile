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
    stage('restart MCP server') {
      when {
        anyOf {
          branch 'master'
        }
      }
      steps {
        sh '''
          # Stop existing MCP server if running
          pkill -f "node mcp-server/index.js --http" || true
          sleep 1
          # Start MCP server in HTTP mode (background, port 3001)
          cd /rgaa-tanaguru
          nohup node mcp-server/index.js --http --port=3001 > /var/log/rgaa-mcp.log 2>&1 &
          sleep 2
          # Verify it started
          curl -sf http://localhost:3001/mcp -X POST \
            -H "Content-Type: application/json" \
            -H "Accept: application/json, text/event-stream" \
            -d '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2025-03-26","capabilities":{},"clientInfo":{"name":"healthcheck","version":"1.0"}}}' \
            > /dev/null
          echo "MCP server started on port 3001"
        '''
      }
    }
  }
}
