What's mangl?
=============

**mangl** isn't an acronym for "**A**ndrea's **G**ame **M**aker **L**anguage **S**tatic **A**nalyser". It is, however, a tool that understands a dialect of Game Maker Language based on that used by Game Maker 8.0.

It's written for PHP 5.6, presumably because that unhealthy obsession with awful programming languages that I seemingly have extends beyond GML.

How do I use it?
================

A better question would be *"Why on earth do you want to?"* After all, Game Maker 8.0 is long-obsolete at this point, and the only project using it that matters is slowly dying. But if you insist, you can do this:

    $ php mangl.php <somedir>

where `<somedir>` is the output of [Gmk-Splitter](https://github.com/Medo42/Gmk-Splitter), or just a directory of GML files.
