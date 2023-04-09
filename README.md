# Board game card creator

This code allows to create images of cards that can be used for some board games.
Each game comes with a list of words, located in `games/<game>/words.txt` . Feel free to add more to the list.

Available games :
- `timesup` (Time's Up!)
- `codenames` (Codenames)

Available misc generations:
- `book-separators` (Labels for bookcases)

#### Running

### Games

```bash
docker run -it --rm -v "$(pwd):/home" bperel/cards:0.1.2 -c 'game=codenames;php games/$game/create_cards.php && php assemble_cards.php games/$game'
```

This will export ready-to-print card sheets to `games/<game>/export/sheets`.

### Misc

```bash
docker run -it --rm -v "$(pwd):/home" bperel/cards:0.1.2 -c 'misc=book-separators; php misc/$misc/create_cards.php && php assemble_cards.php misc/$misc'
```

This will export ready-to-print card sheets to `misc/<misc>/export/sheets`.

#### Building

```bash
docker build . -t bperel/cards:0.1.2
```
