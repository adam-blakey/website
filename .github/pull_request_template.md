<!--
Every commit in this PR must follow Conventional Commits (https://www.conventionalcommits.org), or the "Conventional Commits" check fails.

  type(optional scope): description

Types: build, chore, ci, docs, feat, fix, perf, refactor, revert, style, test
Start the description in lower case and keep the first line under 100 characters.

The release version is worked out from the commits:
  feat(blog)!: drop old URLs        → major (a "!" or a "BREAKING CHANGE:" line in the body)
  fix(contact): validate email      → patch (also perf; only when there's no feat)
  anything else                     → minor

Merges to main wait 15 minutes before deploying and releasing; another merge in that time restarts the wait, so a burst of merges ships as one release.
-->
