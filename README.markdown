# PHP Markdown Extra Extended #

An fork of the [PHP Markdown (Extra) project](http://michelf.com/projects/php-markdown/)(PME), extended with extra syntax, especially focused on adding support for more HTML attributes to outputted HTML, and for outputting HTML5.


## Changes from PHP Markdown ##

### Line break generates a `<br />` (modification) ###
In PME, when you want to insert a `<br />` break tag using Markdown, you end a line with two or more spaces, then type return. This turned out to be more annoying than helpful in my projects, so now you just have to type return. This is also how Markdown works here on GitHub.

Two returns does not insert a `<br />`, but instead creates a new paragraph as usual.

### Support for `cite` attribute on blockquotes (addition) ###
It is now possible to add the optional `cite` attribute to the `blockquote` element.

The new, optional, syntax is:

```markdown
> (cite url) Cited content follows ...
```

The existing rules for and [formatting options](http://daringfireball.net/projects/markdown/syntax#blockquote) for blockquotes still apply.

#### Example:

```markdown
> (http://www.whatwg.org/) Content inside a blockquote must be quoted 
> from another source, whose address, if it has one, 
> may be cited in the `cite` attribute.
```

Will result in the following HTML:

```
<blockquote cite="http://www.whatwg.org/">
<p>Content inside a blockquote must be quoted 
from another source, whose address, if it has one, 
may be cited in the `cite` attribute.</p>
</blockquote>
```