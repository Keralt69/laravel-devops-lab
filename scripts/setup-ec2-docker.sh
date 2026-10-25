#!/usr/bin/env bash
# ==============================================================================
# Script: setup-ec2-docker.sh
# Purpose: Provision fresh Ubuntu 22.04 / 24.04 EC2 instance for Docker
#          Container Deployment (Task 4).
# Usage:   sudo bash setup-ec2-docker.sh
# ==============================================================================

set -euo pipefail

echo ">>> [1/5] Updating packages and installing prerequisites..."
apt-get update -y
apt-get install -y ca-certificates curl gnupg lsb-release

echo ">>> [2/5] Adding official Docker GPG key and repository..."
mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg --yes

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null

echo ">>> [3/5] Installing Docker Engine, CLI, and containerd..."
apt-get update -y
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

echo ">>> [4/5] Adding 'ubuntu' deployment user to the docker group..."
usermod -aG docker ubuntu

# Enable and start Docker service
systemctl enable docker
systemctl restart docker

echo ">>> [5/5] Verifying Docker installation..."
docker --version
docker ps

echo "======================================================================"
echo " Docker installation complete!"
echo " Note: If logged in as 'ubuntu', run 'newgrp docker' or reconnect SSH"
echo "       to apply docker group permissions."
echo "======================================================================"
