#!/bin/bash

# Exit on error
set -e

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}PHP Blog Development Environment${NC}"
echo "============================="

# Function to check if Docker and Docker Compose are installed
check_docker() {
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}Error: Docker is not installed.${NC}"
        exit 1
    fi

    if ! command -v docker-compose &> /dev/null; then
        echo -e "${RED}Error: Docker Compose is not installed.${NC}"
        exit 1
    fi
}

# Create necessary directories if they don't exist
create_dirs() {
    echo -e "${BLUE}Creating directory structure...${NC}"
    mkdir -p src/public src/config src/lib src/models src/controllers src/views/layout src/views/posts
    echo -e "${GREEN}Directory structure created.${NC}"
}

# Start development environment
start_dev() {
    echo -e "${BLUE}Starting development environment...${NC}"
    docker-compose -f docker-compose.dev.yml up -d
    echo -e "${GREEN}Development environment started.${NC}"
    echo -e "Access your application at ${BLUE}http://localhost:8080${NC}"
}

# Stop development environment
stop_dev() {
    echo -e "${BLUE}Stopping development environment...${NC}"
    docker-compose -f docker-compose.dev.yml down
    echo -e "${GREEN}Development environment stopped.${NC}"
}

# Show logs
show_logs() {
    echo -e "${BLUE}Showing logs...${NC}"
    docker-compose -f docker-compose.dev.yml logs -f
}

# Check if any containers are running
check_running() {
    local running=$(docker ps --filter "name=php_dev" --format "{{.Names}}")
    if [[ -n "$running" ]]; then
        return 0
    else
        return 1
    fi
}

# Run bash in the PHP container
enter_container() {
    echo -e "${BLUE}Opening bash shell in PHP container...${NC}"
    docker exec -it php_dev bash
}

# Parse command line arguments
case "$1" in
    up|start)
        check_docker
        create_dirs
        start_dev
        ;;
    down|stop)
        check_docker
        stop_dev
        ;;
    restart)
        check_docker
        stop_dev
        start_dev
        ;;
    logs)
        check_docker
        show_logs
        ;;
    bash|shell)
        check_docker
        if check_running; then
            enter_container
        else
            echo -e "${RED}Development environment is not running.${NC}"
            echo -e "Start it with: ${BLUE}./dev.sh up${NC}"
            exit 1
        fi
        ;;
    *)
        echo "Usage: $0 {up|down|restart|logs|bash}"
        echo "  up      - Start development environment"
        echo "  down    - Stop development environment"
        echo "  restart - Restart development environment"
        echo "  logs    - Show container logs"
        echo "  bash    - Open bash shell in PHP container"
        exit 1
        ;;
esac

exit 0 