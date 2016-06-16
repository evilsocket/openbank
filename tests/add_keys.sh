API_TOKEN=23eX7GbI6DeQ9y3RZ4L6g8pRe41N1Y3osKlvfvU1M2MhZed27t8VH7evgmq7
PAYLOAD='{"keys":[{"label": "Trezor XPUB", "value": "xpub6CMeRLBF6meJ6j4AZJrRGNAtWoKzg5X4w1b7kNeoX9GZQRjTC5kE1XRuZaRa29sz3FTRMafdCQL9Rn9vZqw83QLHhY9ogPEYhuZ272ZFZhM"},{"label": "Coinbase Wallet", "value": "1HvBhsdi6BgNgXZVB9KTwuYmv5NdB1jHsS"},{"label": "CryptoPay Wallet", "value": "3Gef69VY8HdhsPghdbipMrepMCKHWukXd3"},{"label": "Xapo Wallet", "value": "3HqtpSHS9QtWfdXptcu9FMWE3qBtiawcyo"}]}'

curl -H "Content-Type: application/json" -X put -d "$PAYLOAD" "http://localhost/api/v1/me?api_token=$API_TOKEN"
