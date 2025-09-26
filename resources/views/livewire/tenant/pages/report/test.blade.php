<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Paged Report Example</title>
  <script src="https://unpkg.com/pagedjs/dist/paged.polyfill.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
    }

    @page {
      size: A4;
      margin: 2cm;
      marks: crop;

      @bottom-right {
        content: counter(page);
      }
    }

    .pagedjs_page {
      box-shadow: 10px 12px 30px 0 rgba(0, 0, 0, 0.1);
      /* margin-top: 1rem; */
    }

    h1 {
      string-set: chapter content();
    }

    h2 {
      string-set: section content();
    }

    p {
      text-align: justify;
      margin: 0.5em 0;
    }

    /* We'll style CONT'D headers */
    .continued {
      font-style: italic;
    }

    /* .pagedjs_page:not(:first-child) .pagedjs_margin-top-left>.pagedjs_margin-content::after {
      content: var(--pagedjs-string-first-chapter) " (CONT'D)";
    }
    .pagedjs_page:not(:first-child) .pagedjs_margin-top-right>.pagedjs_margin-content::after {
      content: var(--pagedjs-string-first-section) " (CONT'D)";
    } */

  </style>
  <style>
    /* adjust visuals for inserted clones */
    .continued-heading {
    /* match the original styles (override as needed) */
    font-weight: bold;
    /* smaller top gap since we insert at top of page */
    margin-top: 0;
    margin-bottom: 0.4em;
    }
    .continued-chapter { font-size: 32px; }
    .continued-section { font-size: 24px; font-weight: 700; }
    </style>
</head>
<body>

  <h1>Directors' Report</h1>
  <h2>Issues of Shares and Debentures</h2>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <h1>Financial Statements</h1>
  <h2>Balance Sheet</h2>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>


  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <p>Vivamus venenatis, nulla et fermentum porttitor, nisl nunc posuere magna.</p>
  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
  <p>… (repeat lots of paragraphs to force multiple pages)</p>
  <p>Assets, liabilities and equity report content here…</p>

</body>
{{-- <script>
class ContinuedHeadingHandler extends Paged.Handler {
  constructor(chunker, polisher, caller) {
    super(chunker, polisher, caller);
    // remember the most recent chapter (h1) and section (h2)
    this.lastChapter = null;
    this.lastSection = null;
  }

  afterPageLayout(pageFragment, page) {
    const content = pageFragment.querySelector('.pagedjs_page_content');
    if (!content) return;
    const firstEl = content.firstElementChild;
    if (!firstEl) return;

    // --- STEP 1: update stored chapter/section if page contains real headings
    const foundH1 = content.querySelector("h1");
    const foundH2 = content.querySelector("h2");

    if (foundH1) this.lastChapter = foundH1.textContent.trim();
    if (foundH2) this.lastSection = foundH2.textContent.trim();

    // --- STEP 2: if page already starts with a heading, don't inject
    if (/^H[1-6]$/.test(firstEl.tagName)) return;

    // --- STEP 3: inject only if we have something remembered
    let insertBeforeNode = firstEl;

    if (this.lastChapter) {
      const h1clone = document.createElement("h1");
      h1clone.textContent = this.stripCont(this.lastChapter) + " (Cont'd)";
      h1clone.classList.add("continued-heading", "continued-chapter");
      h1clone.style.marginTop = "0";
      content.insertBefore(h1clone, insertBeforeNode);
    }

    if (this.lastSection) {
      const h2clone = document.createElement("h2");
      h2clone.textContent = this.stripCont(this.lastSection) + " (Cont'd)";
      h2clone.classList.add("continued-heading", "continued-section");
      h2clone.style.marginTop = "0.15em";
      content.insertBefore(h2clone, insertBeforeNode);
    }
  }

  // remove any previous Cont’d
  stripCont(s) {
    return s.replace(/\s*\(?Cont(?:'|’)?d\)?\.?$/i, "").trim();
  }
}

Paged.registerHandlers(ContinuedHeadingHandler);
</script> --}}

{{-- <script>
class ContinuedHeadingHandler extends Paged.Handler {
  constructor(chunker, polisher, caller) {
    super(chunker, polisher, caller);
    this.lastChapter = null;
    this.lastSection = null;
  }

  afterPageLayout(pageFragment, page) {
    const content = pageFragment.querySelector('.pagedjs_page_content');
    if (!content) return;
    const firstEl = content.firstElementChild;
    if (!firstEl) return;

    // --- STEP 1: detect new headings at top of this page
    const startsWithH1 = firstEl.tagName === "H1";
    const startsWithH2 = firstEl.tagName === "H2";

    // If this page *starts* with a chapter/section, update state and skip injection
    if (startsWithH1) {
      this.lastChapter = firstEl.textContent.trim();
      this.lastSection = null; // reset section at new chapter
      return;
    }
    if (startsWithH2) {
      this.lastSection = firstEl.textContent.trim();
      return;
    }

    // --- STEP 2: update state if headings exist *inside* page (not at start)
    const foundH1 = content.querySelector("h1");
    const foundH2 = content.querySelector("h2");
    if (foundH1) this.lastChapter = foundH1.textContent.trim();
    if (foundH2) this.lastSection = foundH2.textContent.trim();

    // --- STEP 3: inject CONT'D only if we already have a stored heading
    let insertBeforeNode = firstEl;

    if (this.lastChapter) {
      const h1clone = document.createElement("h1");
      h1clone.textContent = this.stripCont(this.lastChapter) + " (Cont’d)";
      h1clone.classList.add("continued-heading", "continued-chapter");
      h1clone.style.marginTop = "0";
      content.insertBefore(h1clone, insertBeforeNode);
    }

    if (this.lastSection) {
      const h2clone = document.createElement("h2");
      h2clone.textContent = this.stripCont(this.lastSection) + " (Cont’d)";
      h2clone.classList.add("continued-heading", "continued-section");
      h2clone.style.marginTop = "0.15em";
      content.insertBefore(h2clone, insertBeforeNode);
    }
  }

  stripCont(s) {
    return s.replace(/\s*\(?Cont(?:'|’)?d\)?\.?$/i, "").trim();
  }
}

Paged.registerHandlers(ContinuedHeadingHandler);
</script> --}}

<script>
class ContinuedHeadingHandler extends Paged.Handler {
  constructor(chunker, polisher, caller) {
    super(chunker, polisher, caller);
    this.lastChapter = null;
    this.lastSection = null;
  }

  afterPageLayout(pageFragment, page) {
    const content = pageFragment.querySelector('.pagedjs_page_content > div');
    if (!content) return;
    console.log(content.children);
    // if (content.children[0].tagName !== "H1") {

        let insertBeforeNode = content.children[0];

        if (this.lastChapter) {
            const h1clone = document.createElement("div");
            h1clone.textContent = this.lastChapter + " (Cont’d)";
            h1clone.classList.add("continued-heading", "continued-chapter");
            h1clone.style.marginTop = "0";
            content.insertBefore(h1clone, insertBeforeNode);
        }

        if (this.lastSection) {
            // if (!((content.children[0].tagName === "H1" && content.children[1].tagName === "H2") || (content.children[0].tagName === "H2"))) {
                const h2clone = document.createElement("div");
                h2clone.textContent = this.lastSection + " (Cont’d)";
                h2clone.classList.add("continued-heading", "continued-section");
                h2clone.style.marginTop = "0.15em";
                content.insertBefore(h2clone, insertBeforeNode);
            // }
        }
    // }

    const foundH1 = content.querySelector("h1");
    const foundH2 = content.querySelector("h2");
    if (foundH1) this.lastChapter = foundH1.textContent.trim();
    if (foundH2) this.lastSection = foundH2.textContent.trim();
  }
}

Paged.registerHandlers(ContinuedHeadingHandler);
</script>


</html>
