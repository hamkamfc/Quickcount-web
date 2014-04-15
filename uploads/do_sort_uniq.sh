#!/bin/zsh

cat *.csv | sort -t';' -n -k1 -k2 -k3 -k4 -k5 -k6 -k7 -k8r | uniq -u | sort -t';' -n -k1 -k2 -k3 -k4 -k5 -k6 -k7 -u