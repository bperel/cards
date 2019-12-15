# Board game card creator

This code allows to create images of cards that can be used for some board games.
Each game comes with a list of words, located in `games/<game>/words.txt` . Feel free to add more to the list.

Available games :
- `timesup` (Time's Up!)
- `codenames` (Codenames)

#### Running

```bash
docker run -it --rm -v "$(pwd):/home" bperel/cards:0.1.2 -c 'game=codenames;php games/$game/create_cards.php && php games/$game/assemble_cards.php'
```

This will export ready-to-print card sheets to `games/<game>/export/sheets`.

#### Building

```bash
docker build . -t bperel/cards:0.1.2
```
