<!--
Every commit in this PR must follow Conventional Commits (https://www.conventionalcommits.org), or the "Conventional Commits" check fails.

  type(optional scope): description

Types: build, chore, ci, docs, feat, fix, perf, refactor, revert, style, test
Start the description in lower case and keep the first line under 100 characters.

The release version is worked out from the commits:
  feat(blog)!: drop old URLs        → major (a "!" or a "BREAKING CHANGE:" line in the body)
  fix(contact): validate email      → patch (only when there's no feat)
  anything else                     → minor

Merges deploy straight away. Releases are cut every morning at 07:30 UK time, or on demand from Actions → Release → Run workflow.
-->
