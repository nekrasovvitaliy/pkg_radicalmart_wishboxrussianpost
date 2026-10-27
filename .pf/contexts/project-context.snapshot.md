# Project Context Snapshot

## Generated

- generated_at: 2026-10-23T15:52:55Z
- valid_until: 2026-10-30T15:52:55Z

## Freshness

fresh

## Project

- id: pkg-radicalmart-wishboxrussianpost
- name: pkg_radicalmart_wishboxrussianpost

## Flow Root

`.pf/`

## Linked ProcessForge

- version: 1.0.0
- constraint: ^1.0
- install_mode: linked
- distribution: processforge (available)

## Coordination Mode

- project_mode: inherit
- workplace_default_project_mode: simple
- effective_mode: simple
- director_available_at_workplace: false
- director_required: false

## Connected Knowledge Packages

- processforge.core
- project.pkg-radicalmart-wishboxrussianpost

## Platform Contracts

- None.

## Platform Stack

- None.

## Selected Specializations

- None.

## Effective Resources

- None.

## Provided Capabilities

- None.

## Execution Route

- process: None.
- required_capabilities: None.
- required_evidence: None.

## Capability Resolution

- satisfied: 0
- unsatisfied: 0

## Applied Project Overrides

- None.

## Resolved Parameters

- namespaces: None.
- sources: 0
- conflicts: 0

## Resolution Conflicts

- None.

## Required Knowledge Resources

- project.pkg-radicalmart-wishboxrussianpost:project-profile
- project.pkg-radicalmart-wishboxrussianpost:project-artifacts

## Recommended Knowledge Resources

- None.

## Enabled Processes

- None.

## Required Capabilities

- None.

## Optional Capabilities

- None.

## Required Tools

- None.

## Recommended Tools

- None.

## Required MCP

- None.

## Recommended MCP

- None.

## Hard Policies

- public.no_local_absolute_paths
- files.one_writer_per_scope
- secrets.public_exports_sanitized
- runtime.private
- markdown.not_machine_merge_source

## Preferences

- templates.project_overrides_global
- session.prefer_project_context_snapshot

## Project Templates

- None.

## Required Templates

- None.

## Recommended Templates

- None.

## Session Start

Read in this order:

1. AGENTS.md
2. .pf/process-forge.yaml
3. pf.context; verify current snapshot freshness and readiness with the existing CLI when MCP is unavailable
4. pf.work.start / pf.work.state: returned assignment and immutable capsule, identity, scope and obligations
5. pf.work.search / pf.work.resolve: exact returned Work/context selectors and authorized resources
6. extended .pf/AGENTS.md and relevant artifacts/logs/reviews/handoffs on demand

Telemetry root: `.pf/runtime/telemetry`
Events log: `.pf/runtime/events/events.sqlite3`

## Current Risks

- Markdown files are human-readable context and are not authoritative structured merge sources.
- If freshness is stale, refresh before using required capability decisions.

## Refresh Instructions

Run `python bin/pf.py project-context-refresh --project-root <project-root>` from the ProcessForge distribution root, or `python .pf/runtime/bin/pf.py project-context-refresh --project-root .` inside the linked project.
