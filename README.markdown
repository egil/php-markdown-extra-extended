# PHP Markdown Extra Extended

An fork of the [PHP Markdown (Extra) project](http://michelf.com/projects/php-markdown/) (<abbr title="PHP Markdown (Extra)">PME</abbr>), extended with extra syntax, especially focused on adding support for more HTML attributes to outputted HTML, and for outputting HTML5.

Unless explicitly specified, existing Markdown markup works exactly as it did before.

## Changes from PHP Markdown

### Line break generates a `<br />`
In <abbr title="PHP Markdown (Extra)">PME</abbr>, when you want to insert a `<br />` break tag using Markdown, you end a line with two or more spaces, then type return. This turned out to be more annoying than helpful in my projects, so now you just have to type return. This is also how Markdown works with <abbr title="GitHub Flavored Markdown">GFM</abbr>.

Two returns does not insert a `<br />`, but instead creates a new paragraph as usual.

### Support for `cite` attribute on blockquotes
It is now possible to add the optional `cite` attribute to the `blockquote` element.

The new, optional, syntax is:

```markdown
> (cite url) Cited content follows ...
```

#### Example:

```markdown
> (http://www.whatwg.org/) Content inside a blockquote must be quoted 
> from another source, whose address, if it has one, 
> may be cited in the `cite` attribute.
```

Will result in the following HTML:

```html
<blockquote cite="http://www.whatwg.org/">
<p>Content inside a blockquote must be quoted 
from another source, whose address, if it has one, 
may be cited in the `cite` attribute.</p>
</blockquote>
```

#### Breaking changes from <abbr title="PHP Markdown (Extra)">PME</abbr>
The existing rules for and [formatting options](http://daringfireball.net/projects/markdown/syntax#blockquote) for blockquotes still apply. There is one small breaking changes with this addition. If your quote starts with `(` you have two have at least two spaces between the initial `>` and the `(`. E.g.:

```markdown
>  (Ut brisket flank salami.) Cow cupidatat ex t-bone sirloin id. 
> Sunt flank pastrami spare ribs sint id, nulla nisi.
```
Will result in the following HTML:

```html
<blockquote>
  <p>(Ut brisket flank salami.) Cow cupidatat ex t-bone sirloin id.<br>
  Sunt flank pastrami spare ribs sint id, nulla nisi.</p>
</blockquote>
```

### Fenced code block with language support and alternating fence markers (```)
It is now possible to specify the language type of a code block, and use an alternatinge fence markers (```), enabling the same syntax as that of <abbr title="GitHub Flavored Markdown">GFM</abbr>.

#### Example:

	~~~html
	<p>Ut brisket flank salami.  Cow cupidatat ex t-bone sirloin id.</p>
	~~~

Using alternative fence markers:

	```html
	<p>Ut brisket flank salami.  Cow cupidatat ex t-bone sirloin id.</p>
	```

Both will output the following HTML:

```HTML
<pre><code class="language-html">
<p>Ut brisket flank salami.  Cow cupidatat ex t-bone sirloin id.</p>
</code></pre>
```
