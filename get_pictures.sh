#!/bin/bash
cd ~/photos
echo "Debut"
for((i=10000 ; i<30000 ; i++)) do
wget "http://ent.insa-rennes.fr/AnnuaireENT/images/photos/$i.jpg"
done
echo "Fin"
