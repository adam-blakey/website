#!/bin/sh
set -eu

last=$(git tag --list 'v[0-9]*.[0-9]*.[0-9]*' --sort=-v:refname | head -n1)
log=$(git log --no-merges --format=%B "${last:+$last..}${1:-HEAD}")
if [ -z "$log" ]; then
  bump='{print $0}'
elif echo "$log" | grep -qE '^[a-z]+(\([^)]*\))?!:|^BREAKING[ -]CHANGE:'; then
  bump='{print "v"substr($1,2)+1".0.0"}'
elif echo "$log" | grep -qE '^fix(\([^)]*\))?:' && ! echo "$log" | grep -qE '^feat(\([^)]*\))?:'; then
  bump='{print $1"."$2"."$3+1}'
else
  bump='{print $1"."$2+1".0"}'
fi
last=${last:-v0.0.0}
echo "$last $(echo "$last" | awk -F. "$bump")"
