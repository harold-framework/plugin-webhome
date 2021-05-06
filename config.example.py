# ------------------------------------------------------------------------------------------------

if __name__ == "__main__": quit("You may not run this file directly.")
CONFIG = {}; CONFIG["IDs"] = {}; CONFIG["button"] = {}; CONFIG["meta"] = {}; CONFIG["channels"] = {}; CONFIG["channels"]["voice"] = {}; CONFIG["channels"]["text"] = {}; CONFIG["roles"] = []; CONFIG["role_tags"] = {} # DO NOT TOUCH
def VoiceChannel(cid=None,cname=None): return {"type":"channel.voice","cid":cid,"cname":cname} # DO NOT TOUCH
def TextChannel(cid=None,cname=None): return {"type":"channel.text","cid":cid,"cname":cname} # DO NOT TOUCH
def Role(permissions=None,weight=None,cname=None,cid=None,use_inheritance=False,inherit_from=None): return {"type":"role","weight":weight,"cid":cid,"cname":cname,"permissions":permissions,"use_inheritance":use_inheritance,"inherit_from":inherit_from} # DO NOT TOUCH
def RoleInheritance(cname=None,cid=None): return {"type":"role.inheritance","cname":cname,"cid":cid} # DO NOT TOUCH
def TimeRange(minimum,maximum): return {"type":"time.range","min":minimum,"max":maximum} # DO NOT TOUCH

# ------------------------------------------------------------------------------------------------

# As with setting mentioned below. There are some wildcards.
# The 'name' setting can instead be changed to any of the following for special stuff.
#  - None -> Uses username
#  - "USERNAME" -> Obviously, uses username.
#  - "NICKNAME" -> Also obviously, uses nickname. Defaults to username if there isnt a set nickname.

CONFIG["homepage_people"] = [
    {
        "name" : "USERNAME",
        "id" : 123456789123456789,
        "description" : "A description goes here."
    }
]

CONFIG["button"]["name"] = "Join the Community!"
CONFIG["button"]["link"] = "https://discord.gg/abc123" # Could link to Verification plugin if you have it

# Website Settings

# Discord login settings
CONFIG["endpoint"] = "https://discord.com/api/v6" # Do not change, Unless you know what you're doing.

CONFIG["client_id"] = "123456789123456789"
CONFIG["client_secret"] = "XXXXXXXXXXXXXXXXXXXXXXXXXX"
CONFIG["redirect_uri"] = "https://mywebsite.com/login"

# 'community_name' can alternatively be set to None.
# Doing so will make the community name the name of the main
# discord server.

# Should we randomly shuffle the people on the website?
CONFIG["shuffle_people"] = False

# 'community_name' can alternatively be set to None.
# Doing so will make the community name the name of the main
# discord server.

CONFIG["community_name"] = None
CONFIG["community_tag"] = "A nice little description."
CONFIG["discord_link"] = "https://discord.gg/abc123"
CONFIG["default_theme"] = "DARK" # LIGHT or DARK. Users can change this, This is just what theme used when people dont have a prefrence.

# Some meta stuff for you
CONFIG["meta"]["description"] = "A slightly longer description that would go where you send the link that supports rich embeds, Like Discord and Twitter."
CONFIG["meta"]["image"] = "https://mywebsite.com/giphy.gif" # You can also give it a nice image alongside the text.

# Role config
CONFIG["role_tags"]["show_roles"] = True # Should users roles even be displayed?
CONFIG["role_tags"]["show_uncoloured_roles"] = True # Should uncoloured roles be shown?