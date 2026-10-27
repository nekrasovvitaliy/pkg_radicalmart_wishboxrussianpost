# Project Onboarding Report

## Status

applied

## Project

- id: pkg-radicalmart-wishboxrussianpost
- name: pkg_radicalmart_wishboxrussianpost
- type: generic

## Process Boundary

Project onboarding creates root `AGENTS.md`, hidden `.pf/AGENTS.md`, `.pf/agent-entry.json` and project-local `.pf/` state, linked to an existing workplace. It does not recreate the workplace, copy global packages into the project, or write local absolute paths to public files.

## Created First-Run Files

- AGENTS.md
- .pf/AGENTS.md
- .pf/agent-entry.json
- .pf/assignments/first-assignment.yaml (compatibility placeholder, not required Work)
- .pf/contexts/project-context.snapshot.yaml
- .pf/artifacts/project-onboarding-report.md
- .pf/reviews/project-onboarding-review.md
- .pf/handoffs/project-ready-handoff.md

## Doctor Status

fail

## Doctor Output

The full doctor diagnostic is intentionally not copied into this public
artifact. Run `pf doctor-project --project-root .` locally for the current
diagnostic output.

## Fix Hints

- Rerun `pf doctor-project --project-root .`, resolve its FAIL lines, then rerun deterministic repair.
