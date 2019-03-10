import numpy as np
import cv2
import csv
import pandas as pd
import matplotlib
import matplotlib.pyplot as plt

options = pd.read_csv('SKIUTCFINAL.csv', delimiter = ',', header=None)

excel = np.array(["NOM", "PRENOM", "FORFAIT SKI", "SKI/SNOW", "GAMME", "CASQUE", "TAILLE", "POIDS", "POINTURE", "FOODPACK", "ASSURANCE RAPATRIEMENT", "ANNULATION"])

for person in options.itertuples():
    # equipement (24)
    if person[25] == 0:
        equipment = ""
    elif person[25] == 1 and person[27] == 0:
        equipment = "chaussures seules"
    elif person[25] == 1 and person[27] == 1:
        equipment = "ski seuls"
    elif person[25] == 1 and person[27] == 3:
        equipment = "Pack ski"
    elif person[25] == 2 and person[27] == 0:
        equipment = "chaussures seules"
    elif person[25] == 2 and person[27] == 2:
        equipment = "Snow seul"
    elif person[25] == 2 and person[27] == 4:
        equipment = "Pack snow"
    else:
        equipment = "ERROR"

    # GAMME
    if person[26] == 1:
        gamme = "Bronze"
    elif person[26] == 2:
        gamme = "Argent"
    elif person[26] == 3:
        gamme = "Or"
    else:
        gamme = ""
    if equipment == "":
        gamme = ""

    array = np.array([person[5], person[4], 1, equipment, gamme, 0, person[13], person[14], person[15], 0, person[30], person[34]])
    print(array)
    excel = np.vstack((excel,array))

    pd.DataFrame(excel).to_csv('list_options.csv', encoding='utf-8', index=False)
