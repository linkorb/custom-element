Custom Element
==============

This library allows you to define custom elements for usage in (Twig) templates.

Think of it as light-weight SSR (Server Side Rendererd) Web Components.

For example, you can create a new element such as `<Avatar username="alice" imageUrl="https://example.web/alice.webp" bio="Hello world" />`

Then you define a twig template `elements/Avatar.html.twig` such as the following:

```html
<div class="card avatar">
  <div class="card-body">
    <h1>{{username}}</h1>
    {% if imageUrl is defined %}
      <img src="{{ imageUrl }}" class="avatar-image" />
    {% endif %}
    {% if bio is defined %}
      <small>{{ bio }}</small>
    {% endif %}
  </div>
</div>
```

Your custom element can now be rendered using your custom element template.
The template receives a variable for every specified attribute.

Custom elements will only be rendered if they start with an upper-case character (to distinguish standard from custom elements)

## Usage

### Symfony

Add the following to your `services.yaml` to register the renderer and the twig extension:

```yaml
services:
    LinkORB\Component\CustomElement\Twig\CustomElementExtension:
        tags: ['twig.extension']
    LinkORB\Component\CustomElement\CustomElementRenderer: ~
```

### The renderer

You can turn any raw HTML with custom elements into a rendered HTML output like this:

```php
$renderer = new Renderer($twig);
$html = $renderer->render($htmlWithCustomElements);
```

### The Twig extension

You can render custom elements from any Twig template using the filter:

```html
<h1>Example</h1
{{ html|custom_element_render|raw }}
```

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
