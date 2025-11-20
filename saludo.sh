#!/usr/bin/env bash

if [ $# -lt 1 ]; then
  echo "Uso: $0 <nombre>"
  exit 1
fi

nombre="$1"
fecha=$(date "+%Y-%m-%d %H:%M:%S")

echo "Hola, $nombre! La fecha y hora actual es $fecha."
