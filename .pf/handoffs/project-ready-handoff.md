# Handoff: project-onboarding -> governed-work

Objective:
Connect `pkg-radicalmart-wishboxrussianpost` to ProcessForge and prepare for governed work.

Current status:
Project onboarding files were generated.

Input artifacts:
- AGENTS.md
- .pf/AGENTS.md
- .pf/agent-entry.json
- .pf/process-forge.yaml
- .pf/process-forge.local.yaml
- .pf/contexts/project-context.snapshot.yaml
- .pf/assignments/first-assignment.yaml (optional compatibility placeholder)

Files changed:
- AGENTS.md
- .pf/
- .gitignore

Files not to touch:
- Workplace registry files unless the user explicitly requests workplace changes.
- Global packages unless a separate workplace process approves it.

Known issues:
- Detection is observed, not domain-approved.

Required checks:
- `python .pf/runtime/bin/pf.py doctor-project --project-root .`
- `python .pf/runtime/bin/pf.py project-context-check --project-root .`

Next recommended action:
Read root `AGENTS.md`, verify `pf.context`, and use `pf.work.start` for the requested work.
Use its returned Run/assignment/capsule identities with `pf.work.state` and
`pf.work.transition` until `run_completed`. Handle `process_choice_required`
from the offered processes. Do not select the onboarding placeholder as ordinary Work.
`agent-start-prompt` prints current guidance without writing files or trusting stored START.
