#!/usr/bin/env bash
# ==============================================================================
# Script: verify-deployment.sh
# Purpose: Verify deployment health via HTTP endpoint
# Usage:   bash verify-deployment.sh <BASE_URL>
# Example: bash verify-deployment.sh http://localhost:8080
# ==============================================================================

set -euo pipefail

BASE_URL="${1:-http://localhost}"
HEALTH_URL="${BASE_URL}/health"

echo ">>> Checking application health at: ${HEALTH_URL}"

# Attempt up to 10 retries with 3-second sleep
MAX_RETRIES=10
COUNT=1

while [ $COUNT -le $MAX_RETRIES ]; do
    echo "Attempt $COUNT/$MAX_RETRIES..."
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "${HEALTH_URL}" || true)

    if [ "$HTTP_CODE" = "200" ]; then
        echo ">>> SUCCESS! Application returned HTTP 200 OK."
        curl -s "${HEALTH_URL}"
        echo ""
        exit 0
    fi

    echo "Received HTTP code: $HTTP_CODE. Waiting 3s..."
    sleep 3
    COUNT=$((COUNT + 1))
done

echo ">>> ERROR: Healthcheck failed after $MAX_RETRIES attempts."
exit 1
