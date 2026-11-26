{{-- Small helper styles for the applicant pages (badges, key/value rows, tracker). --}}
<style>
    .ap-badge { display: inline-block; padding: .15rem .55rem; border-radius: 999px; font-size: .75rem; font-weight: 600; line-height: 1.3; }
    .ap-badge-green { background: #e6f6ec; color: #146c43; }
    .ap-badge-red { background: #fdecea; color: #a12a20; }
    .ap-badge-blue { background: #e7f0fd; color: #1f55b5; }
    .ap-badge-amber { background: #fff4db; color: #8a5a00; }
    .ap-badge-grey { background: #eceef2; color: #4b5563; }
    .ap-muted { color: #8c9097; font-size: .8125rem; }
    .ap-row { display: flex; justify-content: space-between; gap: 1rem; padding: .5rem 0; border-bottom: 1px solid rgba(128,128,128,.18); font-size: .875rem; }
    .ap-row:last-child { border-bottom: 0; }
    .ap-row > span:first-child { color: #8c9097; }
    .ap-filters { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; margin-bottom: 1rem; }
    .ap-filters .form-control { max-width: 16rem; }
    .ap-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
    .ap-note { padding: .75rem 1rem; border-radius: .375rem; font-size: .875rem; margin-bottom: 1rem; border: 1px solid transparent; }
    .ap-note-ok { background: #e6f6ec; color: #146c43; border-color: #b7e4c7; }
    .ap-note-bad { background: #fdecea; color: #a12a20; border-color: #f5c2c0; }
    .ap-note-info { background: #e7f0fd; color: #1f55b5; border-color: #c5d8f7; }
    .ap-steps { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1rem; }
    .ap-step { padding: .4rem .8rem; border-radius: .375rem; font-size: .8125rem; background: #eceef2; color: #6b7280; }
    .ap-step-done { background: #e6f6ec; color: #146c43; }
    .ap-step-now { background: #e7f0fd; color: #1f55b5; font-weight: 700; }
    .ap-step-bad { background: #fdecea; color: #a12a20; font-weight: 700; }
    .ap-pagination { margin-top: 1rem; }
</style>
