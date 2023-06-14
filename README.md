<!--Edit with usage, simple tutorial, more explenations, etc-->

# TanglePHP

This project is meant to facilitate the creation of responsive, modern, JavaScript like components using PHP.

Modern JavaScript frameworks offer a great deal of features all of which contribute to the creatio of great, responsive user interfaces. TanglePHP aims to do the same but using PHP instead of the classic JavaScript, hopefully with very few dependencies.

## How does it work?

The priciple behind TanglePHP is quite simple. Render the components server side then send them to the client. When the user interacts with a component make an ajax call to the server to retrieve the new state of the component. The state of the component is not saved server side so the memory usage should be relatively low. The componet is basically recreated by the server each time it needs to be updated.