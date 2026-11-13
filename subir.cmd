echo "# tela_tecnico %date% %time%" > README.md
git init
git add . -v
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/Prof-Josimar/tela_tecnico.git
git push -u origin main