## How to use backstopjs:

You can install backstop by entering this directory:

```
cd .ci/test/visual-regression
```

and running

```
npm install
```

The pages scanned are configured in the `backstopConfig.js` file. You'll want to change the `devURL` and `multidevURL`
to the values you want to test. The `devUrl` should represent the currently-approved version of the site, while the
multidevURL is the new code you want to test.

From within this directory, if you type `backstop` and see `zsh: command not found: backstop` you may need to add the
`node_modules/.bin` path to your list of paths in your `~/.zshrc` file.

To run initially, type:

```
backstop reference --config=backstopConfig.js
```

That generates the reference files from the dev site.

To test against your local, run

```
backstop test --config=backstopConfig.js
```
