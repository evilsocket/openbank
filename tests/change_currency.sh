API_TOKEN=23eX7GbI6DeQ9y3RZ4L6g8pRe41N1Y3osKlvfvU1M2MhZed27t8VH7evgmq7
PAYLOAD='{"settings":{"currency": "EUR"}}'
curl -H "Content-Type: application/json" -X put -d "$PAYLOAD" "http://localhost/api/v1/me?api_token=$API_TOKEN"
