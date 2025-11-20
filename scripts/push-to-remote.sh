#!/usr/bin/env bash
set -euo pipefail

REMOTE_NAME="${GIT_REMOTE_NAME:-origin}"
REMOTE_URL="${GIT_REMOTE_URL:-}"
BRANCH="${GIT_BRANCH:-work}"

if [[ -z "$REMOTE_URL" ]]; then
  echo "GIT_REMOTE_URL is required (e.g., https://github.com/<owner>/<repo>.git)" >&2
  exit 1
fi

# Configure remote if missing or different
if git remote get-url "$REMOTE_NAME" >/dev/null 2>&1; then
  CURRENT_URL=$(git remote get-url "$REMOTE_NAME")
  if [[ "$CURRENT_URL" != "$REMOTE_URL" ]]; then
    git remote set-url "$REMOTE_NAME" "$REMOTE_URL"
  fi
else
  git remote add "$REMOTE_NAME" "$REMOTE_URL"
fi

# Fetch to ensure remote connectivity (ignore if empty repo)
git fetch "$REMOTE_NAME" || true

git push -u "$REMOTE_NAME" "$BRANCH"
