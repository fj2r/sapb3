/* 
 * Sapb - Simulation Application Post-Bac. 
 * Copyleft LMN Autun.
 * Utilisation dans le cadre de la licence incluse.
 */

function verifieMail (mail) {
    if ((mail.indexOf("@") >= 0 ) && (mail.indexOf(".") >= 0)) {
        return TRUE
    }
    else {
        alert ("L\'adresse mail n\'est pas valide")
    }
}
