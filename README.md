<h1 align="center">🐟 <code>Babela Fiŝo</code></h1>

*Babela Fiŝo* is a simple tool used to convert markdown files to translatable `PO` files, and vice versa.

The name *Babela Fiŝo* is an Esperanto phrase that means "Babel Fish". The Babel Fish is a small, yellow, leech-like fish, which feeds on brainwave energy received not from its own carrier, but from those around it. It excretes a translation matrix that allows the carrier to instantly understand anything said in any language.

## Usage

To convert a markdown file to a `PO` file, run the following command:

```bash
babela-fish to-po -i <input-markdown> -o <output-po>
```

If you want to combine multiple markdown files into a single `PO` file, you can use the following command:

```bash
babela-fish to-po -i <input-markdown-1> <input-markdown-2> ... -o <output-po>
```

To use a `PO` file to translate a markdown file, run the following command:

```bash
babela-fish to-md -i <input-po> -o <output-markdown>
```

## PO File format

A `PO` file is a text file that contains translations for a particular language. It is used by the `gettext` library to translate text in programs.

For example, consider the following markdown file:

```markdown
# Hello, world!

This is a simple markdown file. [Click here](https://example.com) to learn more.  
Another line of text.
```

The corresponding `POT` file would look like this:

```pot
#
msgid ""
msgstr ""

#: example.md:L1 (H1)
msgid "Hello, world!"
msgstr ""

#: example.md:L3-L4 (P)
msgid "This is a simple markdown file. [Click here](https://example.com) to learn more.  "
"Another line of text."
msgstr ""
```

As you can see, it extracts all block level elements in the markdown file and creates a `msgid` entry for each one. The `msgstr` entry is left empty for the translator to fill in.  
Inline elements are leaved as is, but they can be translated as well.  

You can use `PO` files to translate the text in the markdown file. For example, consider the following `PO` file:

```po
msgid "Hello, world!"
msgstr "Saluton, mondo!"

msgid "This is a simple markdown file. [Click here](https://example.com) to learn more.  "
"Another line of text."
msgstr "Tio estas simpla markdovna dosiero. [Klaku ĉi tie](https://example.com) por lerni pli."
"Alia linio de teksto."
```

When you run the `to-md` command with this `PO` file, the output markdown file will look like this:

```markdown
# Saluton, mondo!

Tio estas simpla markdovna dosiero. [Klaku ĉi tie](https://example.com) por lerni pli.  
Alia linio de teksto.
```

## FAQ

### What is a PO file?

PO files are used to store translations for a particular language. They are used by the `gettext` library to translate text in programs.

### What is a POT file?

POT files are used to store the original text that needs to be translated. They are used by the `gettext` library to generate PO files.

### What is the answer to life, the universe, and everything?

The answer to life, the universe, and everything is 42.

### What is the significance of a towel?

A towel is the most massively useful thing an interstellar hitchhiker can have. Partly it has great practical value. More importantly, a towel has immense psychological value.

### What is the Babel Fish?

The Babel Fish is a small, yellow, leech-like fish that feeds on brainwave energy. It allows the carrier to instantly understand anything said in any language.

## LICENSE


```
cornch/babela-fisho
Copyright (C) 2024 Cornch
Copyright (C) 2024 BinotaLIU

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
```

