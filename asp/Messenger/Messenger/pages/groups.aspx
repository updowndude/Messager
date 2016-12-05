<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="groups.aspx.cs" Inherits="Messenger.groups" MasterPageFile="~/layout.Master" %>
<asp:Content ID="groupsTitle" ContentPlaceHolderID="title" runat="server">
   <link rel="stylesheet" type="text/css" href="../public/dist/myStyle.css" />
    <!-- links to rescoures -->
   <link rel="icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <link rel="shortcut icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <title>Groups</title>
</asp:Content>
<asp:Content ID="groupsPage" ContentPlaceHolderID="body" runat="server">
   <nav class="navbar navbar-light bg-faded">
      <ul class="nav navbar-nav">
         <li class="nav-item active">
            <a class="nav-link" href="#Groups">Groups</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="#About">About</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="#Feedback">Feedback</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="#Adim">Adim</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="#User">
               <asp:Label ID="lblUser" runat="server" Text="fasfsdf"></asp:Label>
            </a>
         </li>
      </ul>
   </nav>
   <section>
      <asp:SqlDataSource ID="getGroups" runat="server" ConnectionString="<%$ ConnectionStrings:Messenger %>" ProviderName="<%$ ConnectionStrings:Messenger.ProviderName %>" SelectCommand="SELECT  groups.name, person.person_id, person.fName, person.lName, person.picture, person.birthday FROM ((person INNER JOIN people_group ON person.person_id = people_group.person_id) INNER JOIN groups ON people_group.groups_id = groups.groups_id) WHERE (person.fName = ?) AND (person.lName = ?) AND (person.birthday = ?)">
         <SelectParameters>
            <asp:SessionParameter Name="fName" SessionField="fName" Type="String" />
            <asp:SessionParameter Name="lName" SessionField="lName" Type="String" />
            <asp:SessionParameter Name="birthday" SessionField="bDay" Type="DateTime" />
         </SelectParameters>
      </asp:SqlDataSource>
      <asp:SqlDataSource ID="sqlAllUsers" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger %>' ProviderName='<%$ ConnectionStrings:Messenger.ProviderName %>' SelectCommand="SELECT fName+ '   ' + lName AS fullPerson FROM [person]"></asp:SqlDataSource>
      <asp:SqlDataSource ID="sqlAllGroups" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger %>' ProviderName='<%$ ConnectionStrings:Messenger.ProviderName %>' SelectCommand="SELECT * FROM [groups]"></asp:SqlDataSource>
      <article id="Groups">
         <asp:DataList ID="DataList1" runat="server" DataSourceID="getGroups">
            <ItemTemplate>
               <div class="card">
                  <div class="card-block">
                     <asp:Label ID="nameLabel" runat="server" Text='<%# Eval("name") %>' />
                  </div>
               </div>
            </ItemTemplate>
         </asp:DataList>
      </article>
      <article id="About">
      </article>
      <article id="Feedback">
      </article>
      <article id="Adim">
      </article>
      <article id="User">
      </article>
   </section>
   <section>
      <form id="addGroupForm" runat="server">
         <article>
            <div class="card card-block">
               <h3 class="card-title">Card title</h3>
               <p class="card-text">User action for a group</p>
               <asp:LinkButton ID="btnLogOut" CssClass="btn btn-primary" runat="server" OnClick="btnLogOut_Click">
                  <span class="glyphicon glyphicon-log-out"></span>
               </asp:LinkButton>
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".addUser"><span class="glyphicon glyphicon-user"></span></button>
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".addGroup"><span class="glyphicon glyphicon-plus-sign"></span></button>
            </div>
            <div class="modal fade addGroup" role="dialog" aria-labelledby="addGroup" aria-hidden="true">
               <div class="modal-dialog modal-md">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Add group</h3>
                     </div>
                     <div class="card card-block">
                        <div class="form-group row">
                           <label for="fName" class="col-xs-2 col-form-label">Group</label>
                           <div class="col-xs-10">
                              <asp:TextBox ID="txtGroupName" runat="server"></asp:TextBox>
                           </div>
                        </div>
                        <asp:Button ID="btnAddGroup"  CssClass="btn btn-primary" runat="server" Text="Add" OnClick="btnAddGroup_Click" />
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal fade addUser" role="dialog" aria-labelledby="addUser" aria-hidden="true">
               <div class="modal-dialog modal-md">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Add user</h3>
                     </div>
                     <div class="card card-block">
                        <div class="form-group row">
                           <label for="dlUser" class="col-xs-2 col-form-label">User</label>
                           <div class="col-xs-10">
                              <asp:DropDownList ID="dlUser" runat="server" DataSourceID="sqlAllUsers" DataValueField="fullPerson" DataTextField="fullPerson" CssClass="form-control"></asp:DropDownList>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="dlGroups" class="col-xs-2 col-form-label">Groups</label>
                           <div class="col-xs-10">
                              <asp:DropDownList ID="dlGroups" runat="server" DataSourceID="sqlAllGroups" DataTextField="name" DataValueField="groups_id" CssClass="form-control"></asp:DropDownList>
                           </div>
                        </div>
                        <asp:Button ID="btnUser"  CssClass="btn btn-primary" runat="server" Text="Add" />
                     </div>
                  </div>
               </div>
            </div>
         </article>
      </form>
   </section>
    <script src="../public/dist/my-com.js" type="text/javascript"></script>
</asp:Content>