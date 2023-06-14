<!--Edit with usage, simple tutorial, more explenations, etc-->

# TanglePHP

TanglePHP is a project designed to simplify the creation of responsive and modern components using PHP instead of traditional JavaScript. It aims to provide a lightweight solution with minimal dependencies.

## How does it work?

The concept behind TanglePHP is straightforward. The components are rendered on the server-side and then sent to the client. When a user interacts with a component, an Ajax call is made to the server to fetch the updated state of the component. As the server does not retain the state of the component, the memory usage remains relatively low. The component is essentially re-created by the server each time it needs to be updated.

## License

TanglePHP is open-source and released under the MIT License. See the [LICENSE](LICENSE.txt) file for more details.
