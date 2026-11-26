// CRA carga este archivo antes de cada suite de Jest.

// jsdom no implementa matchMedia; AddToHomeScreen la usa al montarse.
if (!window.matchMedia) {
    window.matchMedia = (query) => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: () => {},
        removeListener: () => {},
        addEventListener: () => {},
        removeEventListener: () => {},
        dispatchEvent: () => false,
    });
}
