API_TOKEN=23eX7GbI6DeQ9y3RZ4L6g8pRe41N1Y3osKlvfvU1M2MhZed27t8VH7evgmq7
PAYLOAD='[{"label": "aaa", "value": "bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb"}]'

curl -H "Content-Type: application/json" -X delete "http://localhost/api/v1/keys/bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb?api_token=$API_TOKEN"
